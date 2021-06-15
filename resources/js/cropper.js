import Croppr from 'croppr'

export default class Cropper extends Croppr {
  getScaleFactors() {
    const el = this.imageEl

    const [actualWidth, actualHeight] = [el.naturalWidth, el.naturalHeight]

    const { width: elementWidth, height: elementHeight } = el.getBoundingClientRect()

    return [actualWidth / elementWidth, actualHeight / elementHeight]
  }

  resizeToScaled(width, height) {
    const [factorX, factorY] = this.getScaleFactors()

    return this.resizeTo(
      Math.round(width / factorX),
      Math.round(height / factorY),
    )
  }

  moveToScaled(x, y) {
    const [factorX, factorY] = this.getScaleFactors()

    return this.moveTo(
      Math.round(x / factorX),
      Math.round(y / factorY),
    )
  }
}
