import Croppr from 'croppr';

export default class Cropper extends Croppr {
	getScaleFactors() {
		// @ts-expect-error this is not typed
		const el = this.imageEl as HTMLImageElement;

		const [actualWidth, actualHeight] = [el.naturalWidth, el.naturalHeight];

		const { width: elementWidth, height: elementHeight } = el.getBoundingClientRect();

		return [actualWidth / elementWidth, actualHeight / elementHeight];
	}

	resizeToScaled(width: number, height: number) {
		const [factorX, factorY] = this.getScaleFactors();

		return this.resizeTo(
			Math.round(width / factorX),
			Math.round(height / factorY),
		);
	}

	moveToScaled(x: number, y: number) {
		const [factorX, factorY] = this.getScaleFactors();

		return this.moveTo(
			Math.round(x / factorX),
			Math.round(y / factorY),
		);
	}
}
