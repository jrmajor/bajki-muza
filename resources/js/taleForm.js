import axios from 'axios'

window.taleFormData = function (data) {
  return {
    route: data.route,
    director: {
      artist: data.director ?? '',
      isOpen: false,
      people: [],
    },
    lyricists: data.lyricists,
    composers: data.composers,
    actors: data.actors,

    init() {
      this.lyricists.forEach(lyricist => {
        lyricist.isOpen = false
        lyricist.people = []
      })

      this.composers.forEach(composer => {
        composer.isOpen = false
        composer.people = []
      })

      this.actors.forEach(actor => {
        actor.isOpen = false
        actor.people = []
      })
    },


    addArtist(event, type) {
      event.preventDefault()
      this[type].push({
        credit_nr: this[type].length + 1,
        artist: ''
      })
    },

    removeArtist(event, type, index) {
      event.preventDefault()
      this[type].splice(index, 1)
    },

    findArtists(artist) {
      if (artist.artist.length < 2) {
        artist.people = []
      } else {
        axios.get(this.route, {
          params: {
            search: artist.artist
          }
        })
        .then(response => {
          artist.people = response.data
        })
        .catch(response => {
          console.log(response)
        })
      }
    },

    findDirector() {
      if (this.director.artist.length < 2) {
        this.director.people = []
      } else {
        axios.get(this.route, {
          params: {
            search: this.director.artist
          }
        })
        .then(response => {
          this.director.people = response.data
        })
        .catch(response => {
          console.log(response)
        })
      }
    },
  }
}
