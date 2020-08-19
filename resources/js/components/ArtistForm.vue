<template>
    <div class="flex flex-col space-y-5">

        <div class="flex flex-col">
            <label for="name" class="w-full font-medium pb-1 text-gray-700">Imię i nazwisko</label>
            <input type="text" name="name" v-model="artist.name"
                class="w-full form-input">
        </div>

        <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-5">
            <div class="w-full sm:w-1/2 flex space-x-5">
                <div class="w-1/2 items-stretch flex flex-col">
                    <label for="year" class="w-full font-medium pb-1 text-gray-700">Discogs</label>
                    <input type="text" name="discogs" v-model="artist.discogs" @input="parseDiscogs()"
                        class="w-full form-input">
                </div>
                <div class="w-1/2 items-stretch flex flex-col">
                    <label for="year" class="w-full font-medium pb-1 text-gray-700">imdb</label>
                    <input type="text" name="imdb" v-model="artist.imdb" @input="parseImdb()"
                        class="w-full form-input">
                </div>
            </div>
            <div class="w-full sm:w-1/2 flex flex-col">
                <label for="title" class="w-full font-medium pb-1 text-gray-700">Wikipedia</label>
                <input type="text" name="wikipedia" v-model="artist.wikipedia" @input="parseWikipedia()"
                    class="w-full form-input">
            </div>
        </div>

        <button type="submit"
            class="self-center px-4 py-2 bg-white text-sm font-medium rounded-full shadow-md">
            Zapisz
        </button>
    </div>
</template>

<script>
    export default {
        props: ['artistData'],
        data() {
            return {
                "artist": {
                    "name": "",
                    "discogs": "",
                    "imdb": "",
                    "wikipedia": "",
                }
            }
        },
        mounted() {
            this.artist = this.artistData
        },
        methods: {
            parseDiscogs(event) {
                let patt = /(?:https?:\/\/)(?:www\.)discogs\.com\/artist\/([0-9]*)(?:.*)/i;
                let res = patt.exec(this.artist.discogs);
                if(res)
                    this.artist.discogs = res[1];
            },
            parseImdb(event) {
                let patt = /(?:https?:\/\/)(?:www\.)imdb\.com\/name\/nm([0-9]*)(?:.*)/i;
                let res = patt.exec(this.artist.imdb);
                if(res)
                    this.artist.imdb = res[1];
            },
            parseWikipedia(event) {
                let patt = /(?:https?:\/\/)pl\.wikipedia\.org\/wiki\/([^\?\/]*)(?:.*)/i;
                let res = patt.exec(this.artist.wikipedia);
                if(res)
                    this.artist.wikipedia = res[1];
            }
        }
    }
</script>
