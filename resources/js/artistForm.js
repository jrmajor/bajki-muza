import axios from 'axios'

window.artistFormData = function (data) {
  return {
    route: data.route,
    discogs: {
      isOpen: false,
      value: data.discogs,
      people: [],
    },
    filmPolski: {
      isOpen: false,
      value: data.filmpolski,
      people: [],
    },
    wikipedia: {
      isOpen: false,
      value: data.wikipedia,
      people: [],
    },

    findPeople(type) {
      if (this[type].value.length < 5) {
        this[type].people = []
      } else {
        axios.get(this.route + '/' + (type == 'filmPolski' ? 'filmpolski' : type), {
          params: {
            search: this[type].value
          }
        })
        .then(response => {
          this[type].people = response.data
        })
        .catch(response => {
          console.log(response)
        })
      }
    },
  }
}
