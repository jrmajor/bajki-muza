/* global fetch */
import route from 'ziggy'

window.artistFormData = function (data) {
  return {
    discogs: {
      isOpen: false,
      value: data.discogs,
      people: []
    },

    filmPolski: {
      isOpen: false,
      value: data.filmpolski,
      people: []
    },

    wikipedia: {
      isOpen: false,
      value: data.wikipedia,
      people: []
    },

    photo: {
      preview: '',
      file: '',
      uri: '',
      remove: false
    },

    dimensions: { },

    setPhotoPreview (files) {
      if (files.length === 0) {
        this.photo.preview = ''
      } else {
        this.photo.preview = URL.createObjectURL(files[0])
      }
    },

    fileSize (size) {
      if (size < 1024) {
        return size + 'bytes'
      } else if (size >= 1024 && size < 1048576) {
        return (size / 1024).toFixed(1) + 'KB'
      } else if (size >= 1048576) {
        return (size / 1048576).toFixed(1) + 'MB'
      }
    },

    photoLabelText () {
      if (this.photo.file !== '') return this.$refs.photo.files[0].name
      else if (this.photo.uri !== '') return 'Wybrane z galerii'
      return 'Wybierz plik'
    },

    setPhotoUri (uri) {
      this.photo.uri = this.photo.uri !== uri ? uri : ''
      this.photo.file = ''
      this.photo.remove = false
    },

    findPeople (type) {
      if (this[type].value.length < 5) {
        this[type].people = []
      } else {
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
    }
  }
}
