<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-12 space-y-8">
        <div>
            <p class="text-sm text-indigo-500 font-semibold">Checkout</p>
            <h1 class="text-3xl font-bold">Complete your purchase</h1>
            <p class="text-slate-600 dark:text-slate-300 mt-2">Enter your payment details to unlock this wallpaper instantly.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="md:col-span-2 space-y-6">
                <div class="rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 shadow p-6 space-y-4">
                    <h2 class="text-lg font-semibold">Payment details</h2>
                    <form method="POST" action="{{ route('checkout.store', $wallpaper) }}" class="space-y-4">
                        @csrf
                        <div class="space-y-2">
                            <label for="cardholder_name" class="text-sm font-medium text-slate-700 dark:text-slate-200">Name on card</label>
                            <input id="cardholder_name" name="cardholder_name" type="text" value="{{ old('cardholder_name', auth()->user()?->name) }}" required class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 px-3 py-2" />
                            @error('cardholder_name')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2">
                            <label for="card_number" class="text-sm font-medium text-slate-700 dark:text-slate-200">Card number</label>
                            <input id="card_number" name="card_number" inputmode="numeric" pattern="\d{16}" maxlength="16" minlength="16" type="text" value="{{ old('card_number') }}" required class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 px-3 py-2" />
                            @error('card_number')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid gap-4 md:grid-cols-3">
                            <div class="space-y-2">
                                <label for="postal_code" class="text-sm font-medium text-slate-700 dark:text-slate-200">Postal code</label>
                                <input id="postal_code" name="postal_code" type="text" value="{{ old('postal_code') }}" required class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 px-3 py-2" />
                                @error('postal_code')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-2">
                                <label for="card_expiry" class="text-sm font-medium text-slate-700 dark:text-slate-200">Expiry (MM/YY)</label>
                                <input id="card_expiry" name="card_expiry" type="text" placeholder="08/28" value="{{ old('card_expiry') }}" required class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 px-3 py-2" />
                                @error('card_expiry')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-2">
                                <label for="card_pin" class="text-sm font-medium text-slate-700 dark:text-slate-200">PIN</label>
                                <input id="card_pin" name="card_pin" inputmode="numeric" pattern="\d{4}" maxlength="4" minlength="4" type="password" value="{{ old('card_pin') }}" required class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 px-3 py-2" />
                                @error('card_pin')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <button type="submit" class="w-full rounded-full bg-indigo-600 px-4 py-3 text-white font-semibold hover:bg-indigo-500">Pay & unlock</button>
                    </form>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-100 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 shadow p-6 space-y-4">
                <h2 class="text-lg font-semibold">Order summary</h2>
                <div class="flex items-center gap-3">
                    <img src="{{ route('wallpapers.image', $wallpaper) }}" alt="Wallpaper: {{ $wallpaper->title }}" class="w-20 h-20 object-cover rounded-lg">
                    <div>
                        <p class="text-sm text-slate-500">{{ $wallpaper->categoryLabel() }}</p>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $wallpaper->title }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm text-slate-600">
                    <span>Price</span>
                    <span class="font-semibold text-slate-900 dark:text-white">${{ number_format($wallpaper->price, 2) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm text-slate-600">
                    <span>Service fee</span>
                    <span class="font-semibold text-slate-900 dark:text-white">$0.00</span>
                </div>
                <div class="flex items-center justify-between text-base font-semibold">
                    <span>Total due</span>
                    <span class="text-indigo-600">${{ number_format($wallpaper->price, 2) }}</span>
                </div>
                <p class="text-xs text-slate-500">Card details are used only for this simulated transaction and are not stored.</p>
            </div>
        </div>
    </div>
</x-app-layout>
