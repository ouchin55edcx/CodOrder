<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify Your Email Address</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-md mx-auto my-10 bg-white p-6 rounded-lg shadow-md">
        <div class="text-center mb-6">
            <div class="inline-block p-2 bg-green-500 rounded-full mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Verify Your Email Address</h1>
        </div>
        
        <div class="text-gray-600 mb-6">
            <p class="mb-4">Thanks for signing up! Before getting started, please verify your email address by clicking the button below:</p>
        </div>
        
        <div class="text-center mb-6">
            <a href="{{ $verificationUrl }}" class="inline-block bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1">
                Verify Email Address
            </a>
        </div>

        <div class="mb-6">
            <p class="mb-2">Your temporary password: <strong>{{ $plainPassword }}</strong></p>
            <p>Please change this password after logging in.</p>
        </div>
        
        <div class="text-sm text-gray-500 border-t pt-4">
            <p class="mb-2">If you're having trouble clicking the button, copy and paste the URL below into your web browser:</p>
            <p class="text-xs break-all bg-gray-50 p-2 rounded">{{ $verificationUrl }}</p>
            <p class="mt-4">If you did not request this verification, please ignore this email.</p>
        </div>
        
        <div class="mt-6 pt-4 border-t text-center text-xs text-gray-400">
            <p>&copy; {{ date('Y') }} EcoManager. All rights reserved.</p>
        </div>
    </div>
</body>
</html>