<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                        {{ __('tasks.title') }}
                    </x-nav-link>

                    <x-nav-link :href="route('task_statuses.index')" :active="request()->routeIs('task_statuses.*')">
                        {{ __('navigation.task_statuses') }}
                    </x-nav-link>

                    <x-nav-link :href="route('labels.index')" :active="request()->routeIs('labels.*')">
                        {{ __('labels.title') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="flex items-center ms-6">
                @auth
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500">
                            {{ Auth::user()->name }}
                        </span>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <a
                                href="{{ route('logout') }}"
                                class="text-sm text-gray-500 hover:text-gray-700"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                            >
                                {{ __('navigation.log_out') }}
                            </a>
                        </form>
                    </div>
                @else
                    <div class="flex gap-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            {{ __('auth.log_in') }}
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-gray-700">
                                {{ __('auth.register') }}
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                {{ __('tasks.title') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('task_statuses.index')" :active="request()->routeIs('task_statuses.*')">
                {{ __('navigation.task_statuses') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('labels.index')" :active="request()->routeIs('labels.*')">
                {{ __('labels.title') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link
                            :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                        >
                            {{ __('navigation.log_out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('auth.log_in') }}
                    </x-responsive-nav-link>

                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('auth.register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>
