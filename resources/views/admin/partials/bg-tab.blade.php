<div
  x-data='{
        openPreview: false,
        currentImage: {{ $background ? json_encode(asset("storage/bg/" . $background)) : "null" }},
        selectedExisting: null,
        recentImages: JSON.parse(localStorage.getItem("recentBackgrounds") || "[]"),
        existingFromServer: @json($existingBackgrounds ?? []),

        showFullscreen(img) {
            this.currentImage = this.resolveImage(img);
            this.openPreview = true;
        },

        saveRecent(filename) {
            filename = filename.split("/").pop();
            if (!this.recentImages.includes(filename)) {
                this.recentImages.unshift(filename);
                this.recentImages = this.recentImages.slice(0, 5);
                localStorage.setItem("recentBackgrounds", JSON.stringify(this.recentImages));
            }
        },

        resolveImage(filename) {
            if (!filename) return "";
            if (/^https?:\/\//i.test(filename)) return filename;
            return "{{ asset('storage/bg') }}/" + filename.replace(/^\/+/, "");
        },

        selectExisting(filename) {
            const clicked = filename.split("/").pop();
            const current = this.currentImage ? this.currentImage.split("/").pop() : null;

            if (current === clicked) return;

            if (current && current !== "default.jpg" && !this.recentImages.includes(current)) {
                this.recentImages.unshift(current);
            }

            this.recentImages = this.recentImages.filter(img => img !== clicked);
            this.recentImages = Array.from(new Set(this.recentImages)).slice(0, 5);

            if (!this.recentImages.includes("default.jpg")) {
                this.recentImages.push("default.jpg");
            }

            localStorage.setItem("recentBackgrounds", JSON.stringify(this.recentImages));

            this.selectedExisting = clicked;
            this.currentImage = this.resolveImage(clicked);
            this.openPreview = false;
        }
    }'

    x-init='
        if (currentImage) saveRecent(currentImage.split("/").pop());
        if (!recentImages.includes("default.jpg")) recentImages.push("default.jpg");
        recentImages = recentImages.filter(img => existingFromServer.includes(img));
        localStorage.setItem("recentBackgrounds", JSON.stringify(recentImages));
    '

  class="relative mb-8"
>
  <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label class="block font-medium text-gray-200 mb-3">
      {{ __('Choisir une image') }}
    </label>

    <!-- Preview -->
    <template x-if="currentImage">
      <div class="relative group cursor-pointer rounded-xl overflow-hidden" @click="showFullscreen(currentImage)">
        <img :src="currentImage"
             alt="Image actuelle"
             class="w-full max-h-64 object-cover rounded-xl border border-white/10 shadow-lg transition-transform duration-500 group-hover:scale-105">
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
          <span class="text-gray-100 bg-black/50 px-3 py-1 rounded-lg text-sm backdrop-blur-sm">{{__('Cliquez pour agrandir')}}</span>
        </div>
      </div>
    </template>

    <!-- Ajouter une image -->
    <div class="mt-5">
        <input
            id="background"
            type="file"
            name="background"
            accept="image/*"
            class="block w-full text-sm text-gray-200
                file:m-2 file:mr-4 file:py-2 file:px-4
                file:rounded-lg file:border-0
                file:text-sm file:font-medium
                file:bg-red-400/70 file:text-red-50
                hover:file:bg-red-700/80 file:transition
                bg-white/5 border border-white/10 rounded-lg
                focus:ring-2 focus:ring-red-400 transition duration-300 cursor-pointer"
            @change="
                const file = $event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        currentImage = e.target.result;
                        openPreview = false;
                        selectedExisting = null;
                    };
                    reader.readAsDataURL(file);
                }
            "
        >

        <p class="text-xs text-gray-400 mt-2">{{__('Formats acceptés : JPG, PNG, WEBP. Taille max. 5 Mo.')}}</p>
    </div>

    <input type="hidden" name="existing_background" :value="selectedExisting">

    <!-- Images récentes -->
    <template x-if="recentImages.length > 0">
      <div class="mt-6">
        <h4 class="text-gray-300 text-sm mb-2">Images récentes :</h4>
        <div class="flex flex-wrap gap-3">
          <template x-for="img in recentImages" :key="img">
            <div class="relative rounded-md overflow-hidden border border-white/10 cursor-pointer transition transform hover:scale-105"
                 :class="selectedExisting === img ? 'ring-2 ring-red-400 ring-offset-2 ring-offset-black' : ''"
                 @click="selectExisting(img)">
              <img :src="resolveImage(img)" class="w-24 h-16 object-cover">
              <div class="absolute inset-0 bg-black/30 opacity-0 hover:opacity-100 flex items-center justify-center transition">
                <span class="text-xs text-gray-200 bg-black/50 px-2 py-1 rounded">Utiliser</span>
              </div>
            </div>
          </template>
        </div>
      </div>
    </template>

    <!-- Preview agrandie -->
    <div
        x-show="openPreview"
        x-transition.opacity
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 backdrop-blur-lg cursor-zoom-out"
        style="display: none;"
        @click.self="openPreview = false"
    >
        <div
            x-transition.scale.origin.center
            class="max-w-[90vw] max-h-[90vh] rounded-xl overflow-hidden shadow-2xl p-2"
        >
            <img
                :src="currentImage"
                alt="Aperçu image"
                class="object-contain w-full h-full"
                @click.self="openPreview = false"
            >
        </div>
    </div>

    <button type="submit" class="mt-6 px-4 py-2 bg-red-400 hover:bg-red-700 text-white font-semibold rounded-lg shadow transition">
      {{ __('Enregistrer les modifications') }}
    </button>
  </form>
</div>

<!-- Make sure this script is included once (deferred) -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
