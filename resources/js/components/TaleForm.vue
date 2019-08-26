<template>
    <div>
        <h3>Dane</h3>
        <div class="ml-1">
            Tytuł:
            <input type="text" name="title" class="ml-3 mr-8 my-1" v-model="tale.title" size="40"></input>
            Rok:
            <input type="text" name="year" class="ml-3 mr-8 my-1" v-model="tale.year" size="3"></input>
            №:
            <input type="text" name="nr" class="ml-3 my-1" v-model="tale.nr" size="2"></input>
        </div>

        <br>

        <h3>Okładka</h3>
        <div class="ml-1">
            LastFM image ID:
            <input type="text" name="cover" class="ml-3 my-1" v-model="tale.cover" size="45"></input>
        </div>

        <br>

        <h3>Reżyser</h3>
        <div class="ml-1">
            Reżyser:
            <input type="text" name="director" class="ml-3 my-1" v-model="tale.director" size="45"></input>
        </div>

        <br>

        <div class="flex">
            <div class="mr-10">
                <h3>Słowa</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <td>№</td>
                            <td>Artysta</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(lyricist, index) in tale.lyricists">
                            <td>
                                <input type="text" :name="'lyricists[' + index + '][credit_nr]'" v-model="lyricist.credit_nr" size="1">
                            </td>
                            <td>
                                <input type="text" :name="'lyricists[' + index + '][artist]'" v-model="lyricist.artist" size="35">
                            </td>
                            <td>
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
                            <td>
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

            <div>
                <h3>Muzyka</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <td>№</td>
                            <td>Artysta</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(composer, index) in tale.composers">
                            <td>
                                <input type="text" :name="'composers[' + index + '][credit_nr]'" v-model="composer.credit_nr" size="1">
                            </td>
                            <td>
                                <input type="text" :name="'composers[' + index + '][artist]'" v-model="composer.artist" size="35">
                            </td>
                            <td>
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
                            <td>
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

        <h3>Obsada</h3>
        <table class="table">
            <thead>
                <tr>
                    <td>№</td>
                    <td>Artysta</td>
                    <td>Postaci</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(actor, index) in tale.actors">
                    <td>
                        <input type="text" :name="'actors[' + index + '][credit_nr]'" v-model="actor.credit_nr" size="2">
                    </td>
                    <td>
                        <input type="text" :name="'actors[' + index + '][artist]'" v-model="actor.artist" size="30">
                    </td>
                    <td>
                        <input type="text" :name="'actors[' + index + '][characters]'" v-model="actor.characters" size="35">
                    </td>
                    <td>
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
                    <td>
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
        <button type="submit">submit</button>
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