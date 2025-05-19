@extends('customer.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Terms of Service</h1>
                
                <div class="prose prose-blue max-w-none">
                    <p class="text-gray-600 mb-6">
                        Last updated: {{ date('F d, Y') }}
                    </p>

                    <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">1. Agreement to Terms</h2>
                    <p class="text-gray-600 mb-6">
                        By accessing and using our website, you agree to be bound by these Terms of Service and all applicable laws and regulations. If you do not agree with any of these terms, you are prohibited from using or accessing this site.
                    </p>

                    <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">2. Use License</h2>
                    <p class="text-gray-600 mb-4">
                        Permission is granted to temporarily access the materials on our website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:
                    </p>
                    <ul class="list-disc pl-6 text-gray-600 mb-6">
                        <li>Modify or copy the materials</li>
                        <li>Use the materials for any commercial purpose</li>
                        <li>Attempt to decompile or reverse engineer any software contained on the website</li>
                        <li>Remove any copyright or other proprietary notations from the materials</li>
                        <li>Transfer the materials to another person or "mirror" the materials on any other server</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">3. Product Information</h2>
                    <p class="text-gray-600 mb-6">
                        We strive to display as accurately as possible the colors and images of our products. However, we cannot guarantee that your computer monitor's display of any color will be accurate. We reserve the right to discontinue any product at any time.
                    </p>

                    <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">4. Pricing and Payment</h2>
                    <p class="text-gray-600 mb-4">
                        All prices are subject to change without notice. We reserve the right to modify or discontinue the Service without notice at any time. We shall not be liable to you or to any third-party for any modification, price change, suspension, or discontinuance of the Service.
                    </p>

                    <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">5. Shipping and Delivery</h2>
                    <p class="text-gray-600 mb-6">
                        We aim to process and ship orders as quickly as possible. However, we cannot guarantee delivery times and are not responsible for delays beyond our control. Risk of loss and title for items purchased pass to you upon delivery of the items to the carrier.
                    </p>

                    <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">6. Returns and Refunds</h2>
                    <p class="text-gray-600 mb-6">
                        Our return policy allows for returns within 30 days of delivery. Items must be unused and in their original packaging. Refunds will be processed within 5-7 business days after we receive and inspect the returned item.
                    </p>

                    <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">7. Limitation of Liability</h2>
                    <p class="text-gray-600 mb-6">
                        In no event shall we be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on our website.
                    </p>

                    <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">8. Contact Information</h2>
                    <p class="text-gray-600 mb-6">
                        Questions about the Terms of Service should be sent to us at:
                        <br>
                        <a href="mailto:legal@example.com" class="text-primary hover:text-primary/80">legal@example.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 