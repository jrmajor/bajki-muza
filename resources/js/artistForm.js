/* global fetch */

window.artistFormData = function (data) {
  return {
    route: data.route,

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
      remove: false
    },

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

    findPeople (type) {
      if (this[type].value.length < 5) {
        this[type].people = []
      } else {
        fetch(
          this.route + '/' +
            (type === 'filmPolski' ? 'filmpolski' : type) +
            `?search=${encodeURIComponent(this[type].value)}`
        )
          .then(response => response.json())
          .then(data => {
            this[type].people = data
          })
      }
    }
  }
}
