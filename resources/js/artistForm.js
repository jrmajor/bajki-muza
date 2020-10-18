/* global fetch */
import prettyBytes from 'pretty-bytes'
import route from 'ziggy'

window.artistFormData = function (data) {
  return {
    discogs: {
      value: data.discogs ?? '',
      hovered: null,
      isOpen: false,
      shouldCloseOnBlur: true,
      people: []
    },

    filmPolski: {
      value: data.filmpolski ?? '',
      hovered: null,
      isOpen: false,
      shouldCloseOnBlur: true,
      people: []
    },

    wikipedia: {
      value: data.wikipedia ?? '',
      hovered: null,
      isOpen: false,
      shouldCloseOnBlur: true,
      people: []
    },

    photo: {
      preview: '',
      file: '',
      uri: '',
      initialSource: data.photoSource,
      source: data.photoSource,
      remove: false
    },

    dimensions: { },

    prettyBytes,

    init () {
      this.$watch('photo.file', value => {
        this.setPhotoPreview(this.$refs.photo.files)

        if (value === '') return

        this.photo.uri = this.photo.source = ''
        this.photo.remove = false
      })
    },

    setPhotoPreview (files) {
      if (files.length === 0) {
        this.photo.preview = ''
      } else {
        this.photo.preview = URL.createObjectURL(files[0])
      }
    },

    photoLabelText () {
      if (this.photo.file !== '') return this.$refs.photo.files[0].name
      else if (this.photo.uri !== '') return 'Wybrane z galerii'
      return 'Wybierz plik'
    },

    setPhotoUri (uri) {
      if (this.photo.uri === uri) {
        this.photo.uri = ''
        this.photo.source = this.photo.initialSource
        this.photo.remove = false
        return
      }

      this.photo.uri = uri
      this.photo.file = ''
      this.photo.remove = false
      this.photo.source = uri.indexOf('discogs') > 0 ? 'dicsogs' : 'filmpolski'
    },

    findPeople (type) {
      if (this[type].value.length < 5) {
        this[type].people = []
      } else {
        this[type].isOpen = true

        fetch(
          route('ajax.' + type, {
            search: this[type].value
          })
        )
          .then(response => response.json())
          .then(data => {
            this[type].people = data
          })
      }
    },

    arrow (type, direction) {
      if (this[type].people.length === 0) return

      if (this[type].hovered === null) {
        this[type].hovered = direction === 'up' ? this[type].people.length - 1 : 0
        return
      }

      this[type].hovered = direction === 'up' ? this[type].hovered - 1 : this[type].hovered + 1

      if (this[type].hovered < 0) this[type].hovered = this[type].people.length - 1
      if (this[type].hovered > this[type].people.length - 1) this[type].hovered = 0
    },

    enter (type) {
      if (this[type].hovered !== null) this.select(type, this[type].people[this[type].hovered])
    },

    closeDropdown (type) {
      if (!this[type].shouldCloseOnBlur) {
        this[type].shouldCloseOnBlur = true
        return
      }

      this[type].isOpen = false

      this[type].hovered = null
      this[type].shouldCloseOnBlur = true
    },

    select (type, person) {
      this[type].value = person.id
      this.closeDropdown(type)
    }
  }
}
