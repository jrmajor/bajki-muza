import route from 'ziggy-js'
import artistPhotoPicker from './artistPhotoPicker'

window.artistFormData = function (data) {
  return {
    photo: artistPhotoPicker(data),

    discogs: {
      value: data.discogs,
      hovered: null,
      isOpen: false,
      shouldCloseOnBlur: true,
      people: [],
    },

    filmPolski: {
      value: data.filmpolski,
      hovered: null,
      isOpen: false,
      shouldCloseOnBlur: true,
      people: [],
    },

    wikipedia: {
      value: data.wikipedia,
      hovered: null,
      isOpen: false,
      shouldCloseOnBlur: true,
      people: [],
    },

    dimensions: {},

    init() {
      this.$watch('photo.pickers.upload.file', (value) =>
        this.photo.fileSelected(this.$refs.photo.files, value),
      )

      this.photo.init()
    },

    findPeople(type) {
      if (this[type].value.length < 5) {
        this[type].people = []
      } else {
        this[type].isOpen = true

        fetch(
          route('ajax.' + type, {
            search: this[type].value,
          }),
        )
          .then((response) => response.json())
          .then((data) => {
            this[type].people = data
          })
      }
    },

    arrow(type, direction) {
      if (this[type].people.length === 0) return

      if (this[type].hovered === null) {
        this[type].hovered = direction === 'up' ? this[type].people.length - 1 : 0
        return
      }

      this[type].hovered = direction === 'up' ? this[type].hovered - 1 : this[type].hovered + 1

      if (this[type].hovered < 0) this[type].hovered = this[type].people.length - 1
      if (this[type].hovered > this[type].people.length - 1) this[type].hovered = 0
    },

    enter(type) {
      if (this[type].hovered !== null) this.select(type, this[type].people[this[type].hovered])
    },

    closeDropdown(type) {
      if (!this[type].shouldCloseOnBlur) {
        this[type].shouldCloseOnBlur = true
        return
      }

      this[type].isOpen = false

      this[type].hovered = null
      this[type].shouldCloseOnBlur = true
    },

    select(type, person) {
      this[type].value = person.id
      this.closeDropdown(type)
    },
  }
}
