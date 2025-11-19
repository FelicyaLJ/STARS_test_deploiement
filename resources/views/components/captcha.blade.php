
<div class="w-fit border border-white/30 bg-white/10 flex my-3 items-center justify-center rounded-xl overflow-hidden">
    <img
        class="block object-cover"
        src="{{ captcha_src() }}?{{ time() }}"
        alt="captcha"
        id="captcha-img"
        draggable="false"
    >
</div>

<div class="flex gap-2 items-center">
    <input  {{ $attributes->merge(['class'=>""]) }} type="text" name="captcha" placeholder="Entrez le captcha">
    <button type="button" x-data="{ angle: 0 }" class="text-gray-50"
        @click="
            angle -= 360;
            document.getElementById('captcha-img').src = '{{ captcha_src() }}?' + Math.random();
        "
    >
        <svg xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            :style="`transform: rotate(${angle}deg); transition: transform 600ms; transform-origin: center;`">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
            <path d="M3 3v5h5"/>
        </svg>
    </button>
</div>
