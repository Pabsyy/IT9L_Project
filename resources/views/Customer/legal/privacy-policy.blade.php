@extends('customer.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Privacy Policy</h1>
                
                <div class="prose prose-blue max-w-none">
                    <p class="text-gray-600 mb-6">
                        Last updated: {{ date('F d, Y') }}
                    </p>

                    <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">1. Information We Collect</h2>
                    <p class="text-gray-600 mb-4">
                        We collect information that you provide directly to us, including but not limited to:
                    </p>
                    <ul class="list-disc pl-6 text-gray-600 mb-6">
                        <li>Name and contact information</li>
                        <li>Billing and shipping address</li>
                        <li>Payment information</li>
                        <li>Order history</li>
                        <li>Account preferences</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">2. How We Use Your Information</h2>
                    <p class="text-gray-600 mb-4">
                        We use the information we collect to:
                    </p>
                    <ul class="list-disc pl-6 text-gray-600 mb-6">
                        <li>Process your orders and payments</li>
                        <li>Communicate with you about your orders</li>
                        <li>Send you marketing communications (with your consent)</li>
                        <li>Improve our website and services</li>
                        <li>Comply with legal obligations</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">3. Information Sharing</h2>
                    <p class="text-gray-600 mb-6">
                        We do not sell your personal information. We may share your information with:
                    </p>
                    <ul class="list-disc pl-6 text-gray-600 mb-6">
                        <li>Service providers who assist in our operations</li>
                        <li>Payment processors</li>
                        <li>Shipping partners</li>
                        <li>Legal authorities when required by law</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">4. Your Rights</h2>
                    <p class="text-gray-600 mb-6">
                        You have the right to:
                    </p>
                    <ul class="list-disc pl-6 text-gray-600 mb-6">
                        <li>Access your personal information</li>
                        <li>Correct inaccurate information</li>
                        <li>Request deletion of your information</li>
                        <li>Opt-out of marketing communications</li>
                        <li>Lodge a complaint with supervisory authorities</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">5. Contact Us</h2>
                    <p class="text-gray-600 mb-6">
                        If you have any questions about this Privacy Policy, please contact us at:
                        <br>
                        <a href="mailto:privacy@example.com" class="text-primary hover:text-primary/80">privacy@example.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 