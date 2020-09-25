import './artistPicker'
import axios from 'axios'

window.taleFormData = function (data) {
  return {
    cover: {
      preview: '',
      file: '',
      remove: 0,
    },
    lyricists: data.lyricists.map(lyricist => ({
      artist: lyricist,
      key: Math.random().toString(20).substr(2),
    })),
    composers: data.composers.map(composer => ({
      artist: composer,
      key: Math.random().toString(20).substr(2),
    })),
    actors: data.actors.map(actor => {
      actor.key = Math.random().toString(20).substr(2)
      return actor
    }),

    setCoverPreview(files) {
      if (files.length == 0) {
        this.cover.preview = ''
      } else {
        this.cover.preview = URL.createObjectURL(files[0])
      }
    },

    fileSize(size) {
      if(size < 1024) {
        return size + 'bytes';
      } else if(size >= 1024 && size < 1048576) {
        return (size/1024).toFixed(1) + 'KB';
      } else if(size >= 1048576) {
        return (size/1048576).toFixed(1) + 'MB';
      }
    },

    addArtist(list) {
      this[list].push({
        artist: '',
        key: Math.random().toString(20).substr(2),
        isDragged: false,
      })
    },

    removeArtist(list, index) {
      this[list].splice(index, 1)
    },

    onDragStart(event, list, index) {
      event.dataTransfer.setData('index', index)
      event.dataTransfer.setData('list', list)
      this[list][index].isDragged = true
    },

    onDragEnd(artist) { artist.isDragged = false },

    onDragOver(event, targetList) {
      const list = event.dataTransfer.getData('list')

      if (list === targetList || list === '') event.preventDefault()
    },

    onDrop(event, list, destination) {
      if (event.dataTransfer.getData('list') != list) return

      let currentIndex = parseInt(event.dataTransfer.getData('index'))

      if (currentIndex < destination) destination += 1

      const dragged = this[list][currentIndex]

      const copy = {
        characters: dragged.characters,
        key: dragged.key,
      }

      this[list].splice(destination, 0, copy);

      if (currentIndex > destination) currentIndex += 1

      this[list].splice(currentIndex, 1)
    },
  }
}
