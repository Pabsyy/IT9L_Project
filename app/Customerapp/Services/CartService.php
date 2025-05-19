<?php

namespace App\Customerapp\Services;

use Illuminate\Session\SessionManager;
use stdClass;

class CartService
{
    protected $session;
    protected $instance = 'shopping-cart';

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    public function add($id, $name, $quantity = 1, $price = 0.0, $options = [])
    {
        $cartItem = [
            'id' => $id,
            'name' => $name,
            'qty' => $quantity,
            'price' => $price,
            'options' => $options,
            'rowId' => $this->generateRowId($id, $options)
        ];

        $content = $this->getContent();
        
        // Check if item already exists in cart
        if (isset($content[$cartItem['rowId']])) {
            $cartItem['qty'] += $content[$cartItem['rowId']]['qty'];
        }
        
        $content[$cartItem['rowId']] = $cartItem;
        $this->session->put($this->instance, $content);

        return $cartItem;
    }

    public function content()
    {
        return collect($this->getContent())->map(function ($item) {
            return (object) $item;
        });
    }

    public function remove($rowId)
    {
        $content = $this->getContent();
        unset($content[$rowId]);
        $this->session->put($this->instance, $content);
    }

    public function count()
    {
        return $this->content()->sum('qty');
    }

    public function total()
    {
        return $this->content()->sum(function ($item) {
            return $item->qty * $item->price;  // Using qty instead of Quantity
        });
    }

    public function subtotal()
    {
        return $this->content()->sum(function ($item) {
            return $item->qty * $item->price;  // Using qty instead of Quantity
        });
    }

    public function update($rowId, $quantity)
    {
        $content = $this->getContent();
        
        if (isset($content[$rowId])) {
            $content[$rowId]['qty'] = $quantity;
            $this->session->put($this->instance, $content);
        }
        
        return isset($content[$rowId]) ? (object)$content[$rowId] : null;
    }

    protected function getContent()
    {
        return $this->session->get($this->instance, []);
    }

    protected function generateRowId($id, $options)
    {
        return md5($id . serialize($options));
    }
}
