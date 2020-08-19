<template>
    <div class="flex flex-col space-y-5">
        <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-5">
            <div class="w-full sm:w-1/2 flex flex-col">
                <label for="title" class="w-full font-medium pb-1 text-gray-700">Tytuł</label>
                <input type="text" name="title" v-model="tale.title"
                    class="w-full form-input">
            </div>
            <div class="w-full sm:w-1/2 flex space-x-5">
                <div class="w-1/2 items-stretch flex flex-col">
                    <label for="year" class="w-full font-medium pb-1 text-gray-700">Rok</label>
                    <input type="text" name="year" v-model="tale.year"
                        class="w-full form-input">
                </div>
                <div class="w-1/2 items-stretch flex flex-col">
                    <label for="nr" class="w-full font-medium pb-1 text-gray-700">№</label>
                    <input type="text" name="nr" v-model="tale.nr"
                        class="w-full form-input">
                </div>
            </div>
        </div>

        <div class="flex flex-col">
            <label for="cover" class="w-full font-medium pb-1 text-gray-700">Okładka w LastFM</label>
            <input type="text" name="cover" v-model="tale.cover"
                class="w-full form-input">
        </div>

        <div class="flex flex-col space-y-2">
            <div class="flex flex-col">
                <label for="director" class="w-full font-medium pb-1 text-gray-700">Reżyser</label>
                <input type="text" name="director" v-model="tale.director"
                    class="w-full form-input">
            </div>

            <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-5">
                <div class="w-full md:w-1/2 flex flex-col">
                    <span class="w-full -mb-1.5 font-medium text-gray-700">Słowa</span>
                    <table>
                        <thead>
                            <tr>
                                <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">№</span></td>
                                <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">Artysta</span></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(lyricist, index) in tale.lyricists">
                                <td class="pr-1">
                                    <input type="text" :name="'lyricists[' + index + '][credit_nr]'" v-model="lyricist.credit_nr"
                                        class="w-10 text-sm form-input">
                                </td>
                                <td class="px-1">
                                    <input type="text" :name="'lyricists[' + index + '][artist]'" v-model="lyricist.artist"
                                        class="w-full form-input">
                                </td>
                                <td class="pl-1">
                                    <button
                                        @click="removeLyricist(index, $event)"
                                        class="relative mt-1 px-0 pb-0 w-5 bg-red-500 text-red-100 rounded-full focus:bg-red-700"
                                        style="padding-top: 100%">
                                        <div class="absolute inset-0" style="top: -0.1rem">-</div>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td class="pl-1">
                                    <button
                                        @click="addLyricist($event)"
                                        class="relative mt-1 px-0 pb-0 w-5 bg-green-500 text-green-100 rounded-full focus:bg-green-700"
                                        style="padding-top: 100%">
                                        <div class="absolute inset-0" style="top: -0.2rem">+</div>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="w-full md:w-1/2 flex flex-col">
                    <span class="w-full -mb-1.5 font-medium text-gray-700">Muzyka</span>
                    <table>
                        <thead>
                            <tr>
                                <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">№</span></td>
                                <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">Artysta</span></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(composer, index) in tale.composers">
                                <td class="pr-1">
                                    <input type="text" :name="'composers[' + index + '][credit_nr]'" v-model="composer.credit_nr"
                                        class="w-10 text-sm form-input">
                                </td>
                                <td class="px-1">
                                    <input type="text" :name="'composers[' + index + '][artist]'" v-model="composer.artist"
                                        class="w-full form-input">
                                </td>
                                <td class="pl-1">
                                    <button
                                        @click="removeComposer(index, $event)"
                                        class="relative mt-1 px-0 pb-0 w-5 bg-red-500 text-red-100 rounded-full focus:bg-red-700"
                                        style="padding-top: 100%">
                                        <div class="absolute inset-0" style="top: -0.1rem">-</div>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td class="pl-1">
                                    <button
                                        @click="addComposer($event)"
                                        class="relative mt-1 px-0 pb-0 w-5 bg-green-500 text-green-100 rounded-full focus:bg-green-700"
                                        style="padding-top: 100%">
                                        <div class="absolute inset-0" style="top: -0.2rem">+</div>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col">
                <span class="w-full -mb-1.5 font-medium text-gray-700">Obsada</span>
                <table>
                    <thead>
                        <tr>
                            <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">№</span></td>
                            <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">Artysta</span></td>
                            <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">Postaci</span></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(actor, index) in tale.actors">
                            <td class="pr-1">
                                <input type="text" :name="'actors[' + index + '][credit_nr]'" v-model="actor.credit_nr"
                                    class="w-10 text-sm form-input">
                            </td>
                            <td class="px-1">
                                <input type="text" :name="'actors[' + index + '][artist]'" v-model="actor.artist"
                                    class="form-input">
                            </td>
                            <td class="px-1">
                                <input type="text" :name="'actors[' + index + '][characters]'" v-model="actor.characters"
                                    class="form-input">
                            </td>
                            <td class="pl-1">
                                <button
                                    @click="removeActor(index, $event)"
                                    class="relative mt-1 px-0 pb-0 w-5 bg-red-500 text-red-100 rounded-full focus:bg-red-700"
                                    style="padding-top: 100%">
                                    <div class="absolute inset-0" style="top: -0.1rem">-</div>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="pl-1">
                                <button
                                    @click="addActor($event)"
                                    class="relative mt-1 px-0 pb-0 w-5 bg-green-500 text-green-100 rounded-full focus:bg-green-700"
                                    style="padding-top: 100%">
                                    <div class="absolute inset-0" style="top: -0.2rem">+</div>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
        props: ['taleData'],
        data() {
            return {
                "tale": {
                    "title": "",
                    "year": "",
                    "director": "",
                    "cover": "",
                    "nr": "",
                    "lyricists": [],
                    "composers": [],
                    "actors": []
                }
            }
        },
        mounted() {
            this.tale = this.taleData
        },
        methods: {
            addLyricist(event) {
                if (event) event.preventDefault()
                this.tale.lyricists.push({
                    credit_nr: this.tale.lyricists.length + 1,
                    artist: ""
                })
            },
            addComposer(event) {
                if (event) event.preventDefault()
                this.tale.composers.push({
                    credit_nr: this.tale.composers.length + 1,
                    artist: ""
                })
            },
            addActor(event) {
                if (event) event.preventDefault()
                this.tale.actors.push({
                    credit_nr: this.tale.actors.length + 1,
                    artist: "",
                    characters: ""
                })
            },
            removeLyricist(index, event) {
                if (event) event.preventDefault()
                this.tale.lyricists.splice(index, 1)
            },
            removeComposer(index, event) {
                if (event) event.preventDefault()
                this.tale.composers.splice(index, 1)
            },
            removeActor(index, event) {
                if (event) event.preventDefault()
                this.tale.actors.splice(index, 1)
            }
        }
    }
</script>
