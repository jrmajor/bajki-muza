/* global fetch */
import route from 'ziggy'

window.artistPickerData = function () {
  return {
    name: undefined,
    value: undefined,
    hovered: null,
    isOpen: false,
    shouldCloseOnBlur: true,
    search: '',
    artists: [],

    init () {
      this.name = this.$el.parentElement.getAttribute('data-picker-name')
      this.value = this.$el.parentElement.getAttribute('data-picker-value')
    },

    updateIndexes () {
      this.name = this.$el.parentElement.getAttribute('data-picker-name')
    },

    findArtists (event) {
      if (this.value.length < 2) {
        this.artists = []
      } else {
        this.isOpen = true

        fetch(
          route('ajax.artists', {
            search: this.value
          })
        )
          .then(response => response.json())
          .then(data => {
            this.artists = data
            if (this.hovered > this.artists.length - 1) this.hovered = null
          })
      }
    },

    arrow (direction) {
      if (this.artists.length === 0) return

      if (this.hovered === null) {
        this.hovered = direction === 'up' ? this.artists.length - 1 : 0
        return
      }

      this.hovered = direction === 'up' ? this.hovered - 1 : this.hovered + 1

      if (this.hovered < 0) this.hovered = this.artists.length - 1
      if (this.hovered > this.artists.length - 1) this.hovered = 0
    },

    enter () {
      if (this.hovered !== null) this.select(this.artists[this.hovered])
    },

    closeDropdown () {
      if (!this.shouldCloseOnBlur) {
        this.shouldCloseOnBlur = true
        return
      }

      this.isOpen = false

      this.hovered = null
      this.shouldCloseOnBlur = true
    },

    select (artist) {
      this.value = artist
      this.closeDropdown()
    }
  }
}
