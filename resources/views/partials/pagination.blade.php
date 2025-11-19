<div class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4 text-gray-300">
    <div class="flex items-center gap-2">
        <label for="perPage">{{__('Afficher :')}}</label>
        <select id="perPage"
            class="bg-red-800/30 border border-red-400 rounded-lg px-4 text-white py-1 text-sm">
            <option class="bg-red-800/30 my-1" value="10" selected>10</option>
            <option class="bg-red-800/30 my-1" value="25">25</option>
            <option class="bg-red-800/30 my-1" value="50">50</option>
            <option class="bg-red-800/30 my-1" value="100">100</option>
        </select>
        <span>{{__('éléments')}}</span>
    </div>

    <div id="pagination" class="flex gap-1"></div>
</div>
