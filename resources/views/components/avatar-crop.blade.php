<!-- Avatar Upload + Crop Modal -->
<div x-data="avatarCrop()" x-cloak>
    <!-- Trigger: pilih file -->
    <div class="flex items-center gap-3 flex-wrap">
        <input type="file" id="avatar-input" accept="image/jpeg,image/png,image/jpg,image/webp" @change="loadImage($event)" class="block text-sm text-gray-500 dark:text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        @if(Auth::user()->hasAvatar())
            <button type="button" @click="$dispatch('confirm-remove-avatar')" class="text-sm text-red-600 hover:text-red-800">Hapus Foto</button>
        @endif
    </div>
    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">JPG, PNG, atau WEBP. Akan dipotong kotak 1:1 dan dikompres otomatis.</p>

    <!-- Crop Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-900/60 dark:bg-gray-950/60" @click="cancel()"></div>
            <div class="relative bg-white dark:bg-gray-900 rounded-xl shadow-xl max-w-md w-full p-6 z-10" x-show="showModal" x-transition>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Atur Foto Profil</h3>
                    <button type="button" @click="cancel()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Geser dan zoom untuk mengatur posisi foto. Hasil akan dipotong kotak (1:1).</p>

                <div class="mb-4">
                    <img x-ref="cropImage" :src="imageSrc" class="max-w-full max-h-64 mx-auto rounded-lg" style="display: none;" x-show="imageSrc">
                </div>

                <div class="flex items-center justify-center gap-2 mb-4" x-show="imageSrc">
                    <button type="button" @click="zoomOut()" class="p-2 rounded-md border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
                        </svg>
                    </button>
                    <button type="button" @click="zoomIn()" class="p-2 rounded-md border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                        </svg>
                    </button>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="cancel()" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition">Batal</button>
                    <button type="button" @click="save()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm" x-show="imageSrc">Simpan Foto</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload progress -->
    <div x-show="uploading" x-cloak class="mt-2 flex items-center gap-2 text-sm text-indigo-600">
        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        Mengunggah...
    </div>

    <!-- Hidden form untuk submit -->
    <form id="avatar-form" method="POST" action="{{ route('profile.avatar') }}" class="hidden">
        @csrf
        <input type="hidden" name="avatar" x-ref="avatarData">
    </form>
</div>

<!-- Cropper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

<script>
function avatarCrop() {
    return {
        showModal: false,
        imageSrc: null,
        cropper: null,
        uploading: false,

        loadImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maks 10MB.');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.imageSrc = e.target.result;
                this.showModal = true;
                this.$nextTick(() => {
                    this.initCropper();
                });
            };
            reader.readAsDataURL(file);
            event.target.value = '';
        },

        initCropper() {
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
            const img = this.$refs.cropImage;
            img.src = this.imageSrc;
            img.style.display = 'block';

            this.cropper = new Cropper(img, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.9,
                background: false,
                responsive: true,
                cropBoxResizable: true,
                minCropBoxWidth: 100,
                minCropBoxHeight: 100,
            });
        },

        zoomIn() {
            if (this.cropper) this.cropper.zoom(0.1);
        },

        zoomOut() {
            if (this.cropper) this.cropper.zoom(-0.1);
        },

        cancel() {
            this.showModal = false;
            this.imageSrc = null;
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },

        save() {
            if (!this.cropper) return;

            this.uploading = true;
            this.showModal = false;

            const canvas = this.cropper.getCroppedCanvas({
                width: 400,
                height: 400,
                imageSmoothingQuality: 'high',
            });

            canvas.toBlob((blob) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.$refs.avatarData.value = e.target.result;
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                    this.uploading = false;
                    document.getElementById('avatar-form').submit();
                };
                reader.readAsDataURL(blob);
            }, 'image/jpeg', 0.85);
        },
    };
}
</script>


