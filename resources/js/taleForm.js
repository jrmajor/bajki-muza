import './artistPicker'
import axios from 'axios'

window.taleFormData = function (data) {
  return {
    cover: {
      preview: '',
      file: '',
      remove: 0,
    },
    lyricists: data.lyricists,
    composers: data.composers,
    actors: data.actors,

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

    addArtist(type) {
      this[type].push({
        credit_nr: this[type].length + 1,
        artist: '',
      })
    },

    removeArtist(type, index) {
      this[type].splice(index, 1)
    },
  }
}
