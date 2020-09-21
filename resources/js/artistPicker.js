import axios from 'axios'

window.artistPickerData = function (data) {
  return {
    route: data.route,
    name: undefined,
    value: undefined,
    hovered: null,
    isOpen: false,
    shouldCloseOnBlur: true,
    search: '',
    artists: [],

    findArtists(event) {
      if (this.value.length < 2) {
        this.artists = []
      } else {
        axios.get(this.route, {
          params: {
            search: this.value
          }
        })
        .then(response => {
          this.artists = response.data
          if (this.hovered > this.artists.length - 1) this.hovered = null
        })
        .catch(response => {
          console.log(response)
        })
      }
    },

    arrow(direction) {
      if (this.artists.length == 0) return

      if (this.hovered == null) {
        return this.hovered = direction == 'up' ? this.artists.length - 1 : 0
      }

      this.hovered = direction == 'up' ? this.hovered - 1 : this.hovered + 1

      if (this.hovered < 0) this.hovered = this.artists.length - 1
      if (this.hovered > this.artists.length - 1) this.hovered = 0
    },

    enter() {
      if (this.hovered != null) this.select(this.artists[this.hovered])
    },

    closeDropdown() {
      if (! this.shouldCloseOnBlur) return this.shouldCloseOnBlur = true

      this.isOpen = false

      this.hovered = null
      this.shouldCloseOnBlur = true
    },

    select(artist) {
      this.value = artist
      this.isOpen = false
    },
  }
}
