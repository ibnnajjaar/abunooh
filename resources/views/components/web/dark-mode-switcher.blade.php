<div x-cloak x-data="darkModeSwitcher" x-init="initialize()" style="height:44px;width:44px;"
     @click="toggleDarkMode"
     class="transition ease-in-out rounded-md bg-gray-200 dark:hover:bg-gray-200 dark:bg-gray-700 cursor-pointer hover:bg-gray-600 hover:text-gray-200 flex justify-center items-center text-gray-600 dark:hover:text-gray-600 dark:text-slate-200">
    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg"
         class="transition ease-in-out h-6 w-6"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg"
         class="transition ease-in-out h-6 w-6"
         fill="none"
         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('darkModeSwitcher', () => ({
            darkMode: false,
            toggleDarkMode() {
                this.darkMode = !this.darkMode;
                this.setDarkMode(this.darkMode);
            },
            initialize() {
                this.darkMode = localStorage.getItem('darkMode') === 'true';
                this.setDarkMode(this.darkMode);
            },
            setDarkMode(value) {
                document.documentElement.classList.toggle('dark', value);
                localStorage.setItem('darkMode', value);
            }
        }))
    });
</script>




