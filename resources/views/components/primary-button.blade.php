<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-3 bg-primary border border-transparent bg-blue-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-primary focus:bg-gray-primary active:bg-gray-primary focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
