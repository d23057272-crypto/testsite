@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-600 dark:border-gray-300 bg-gray-200 h-10 pl-2 dark:text-gray-800 focus:border-gray-500 text-gray-800 dark:focus:border-gray-500 focus:ring-gray-500 dark:focus:ring-gray-500 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500']) !!}>