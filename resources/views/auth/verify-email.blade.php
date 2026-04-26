<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Thanks for signing up! Before getting started, please verify your email by clicking the link
        we just emailed to you. If you didn’t receive the email, we will gladly send you another.
    </div>

    @if (session('message'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <div>
            <x-primary-button>
                Resend Verification Email
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
