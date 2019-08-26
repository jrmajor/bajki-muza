<template>
    <div>
        <h3>Dane</h3>
        <div class="ml-1">
            Imię i nazwisko:
            <input type="text" name="name" class="ml-3 my-1" v-model="artist.name" size="40"></input>
        </div>

        <br>

        <h3>Zewnętrzne</h3>
        <div class="ml-1">
            Discogs:
            <input type="text" name="discogs" class="ml-3 mr-8 my-1" v-model="artist.discogs" @input="parseDiscogs()" size="5"></input>
            imdb:
            <input type="text" name="imdb" class="ml-3 mr-8 my-1" v-model="artist.imdb" @input="parseImdb()" size="7"></input>
            Wikipedia:
            <input type="text" name="wikipedia" class="ml-3 my-1" v-model="artist.wikipedia" @input="parseWikipedia()" size="26"></input>
        </div>

        <br>

        <button type="submit">submit</button>
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