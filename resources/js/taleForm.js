import './artistPicker'
import prettyBytes from 'pretty-bytes'

window.taleFormData = function (data) {
  return {
    cover: {
      preview: '',
      file: '',
      remove: false
    },

    credits: data.credits.map(credit => {
      credit.key = Math.random().toString(20).substr(2)
      return credit
    }),

    actors: data.actors.map(actor => {
      actor.key = Math.random().toString(20).substr(2)
      return actor
    }),

    prettyBytes,

    init ($dispatch) {
      this.$watch('cover.file', value => {
        this.setCoverPreview(this.$refs.cover.files)
        if (value !== '') this.cover.remove = false
      })

      this.$watch('credits', value => {
        this.$nextTick(() => $dispatch('artists-indexes-updated'))
      })

      this.$watch('actors', value => {
        this.$nextTick(() => $dispatch('artists-indexes-updated'))
      })
    },

    setCoverPreview (files) {
      if (files.length === 0) {
        this.cover.preview = ''
      } else {
        this.cover.preview = URL.createObjectURL(files[0])
      }
    },

    addActor () {
      this.actors.push({
        key: Math.random().toString(20).substr(2),
        isDragged: false
      })
    },

    addCredit () {
      this.credits.push({
        nr: 0,
        key: Math.random().toString(20).substr(2)
      })
    },

    removeArtist (list, index) {
      this[list].splice(index, 1)
    },

    onDragStart (event, list, index) {
      event.dataTransfer.setData('index', index)
      event.dataTransfer.setData('list', list)

      this[list][index].isDragged = true
    },

    onDragEnd (artist) { artist.isDragged = false },

    onDragOver (event, targetList, destination) {
      const list = event.dataTransfer.getData('list')

      if (list === targetList || list === '') event.preventDefault()

      const currentIndex = parseInt(event.dataTransfer.getData('index'))

      if (list !== targetList || isNaN(currentIndex)) return

      if (currentIndex === destination) return

      if (currentIndex < destination) {
        this[list][destination].isDraggedOver = 'fromAbove'
      } else {
        this[list][destination].isDraggedOver = 'fromBelow'
      }
    },

    onDragLeave (artist) {
      artist.isDraggedOver = false
    },

    onDrop (event, list, destination) {
      if (event.dataTransfer.getData('list') !== list) return

      const currentIndex = parseInt(event.dataTransfer.getData('index'))

      if (currentIndex === destination) return

      const priorDestinationElement = this[list][destination]

      priorDestinationElement.noTransitions = true
      priorDestinationElement.isDraggedOver = false

      const dragged = this[list][currentIndex]

      // dragged is alpine proxy
      const draggedClone = {
        characters: dragged.characters,
        key: dragged.key
      }

      // if the element is dragged from above, insert it below
      // if the element is dragged from below, insert it above
      if (currentIndex < destination) destination += 1

      this[list].splice(destination, 0, draggedClone)

      // if element was inserted above original location,
      // its index increased by one
      const indexToDelete = destination < currentIndex
        ? currentIndex + 1
        : currentIndex

      this[list].splice(indexToDelete, 1)

      // this element will be used to imitate place after moved element
      // by adding padding, which will then be transitioned back to normal
      const elementNearDeleted = this[list][currentIndex]

      elementNearDeleted.noTransitions = true

      if (currentIndex < destination) {
        elementNearDeleted.hasDeletedElement = 'above'
      } else {
        elementNearDeleted.hasDeletedElement = 'below'
      }

      this.$nextTick(() => {
        elementNearDeleted.noTransitions = false
        elementNearDeleted.hasDeletedElement = false

        priorDestinationElement.noTransitions = false
      })

      this[list].forEach((artist) => { artist.isDraggedOver = false })
    }
  }
}
