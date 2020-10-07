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
