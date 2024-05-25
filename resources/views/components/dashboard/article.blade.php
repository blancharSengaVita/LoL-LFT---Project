<article class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
    <div class="flex justify-between gap-x-4 pb-1 items-center sm:flex-nowrap">
        <h3 class="text-base font-semibold leading-6 text-gray-900">{{$title}}</h3>
        <div class="flex">
            <button type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </button>
            <button type="button" class="text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                </svg>
            </button>
        </div>
    </div>
    <div x-data="{open: false}" class=" sm:w-12/12">
        {{$slot}}
    </div>
</article>
