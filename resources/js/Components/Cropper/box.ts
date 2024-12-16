export interface Box {
	x: number;
	y: number;
	width: number;
	height: number;
}

type Coordinates = [number, number];

export function round(box: Box): Box {
	return {
		x: Math.round(box.x),
		y: Math.round(box.y),
		width: Math.round(box.width),
		height: Math.round(box.height),
	};
}

function resize(
	box: Box,
	newWidth: number,
	newHeight: number,
	origin: Coordinates,
): Box {
	const fromX = box.x + (box.width * origin[0]);
	const fromY = box.y + (box.height * origin[1]);

	return {
		x: fromX - (newWidth * origin[0]),
		y: fromY - (newHeight * origin[1]),
		width: newWidth,
		height: newHeight,
	};
}

function scale(box: Box, factor: number, origin: Coordinates): Box {
	const newWidth = box.width * factor;
	const newHeight = box.height * factor;

	return resize(box, newWidth, newHeight, origin);
}

function getAbsolutePoint(box: Box, [x, y]: Coordinates): Coordinates {
	return [
		box.x + (box.width * x),
		box.y + (box.height * y),
	];
}

export function constrainToRatio(
	box: Box,
	ratio: number,
	origin: Coordinates,
	grow: 'width' | 'height',
): Box {
	return grow === 'width'
		? resize(box, box.height / ratio, box.height, origin)
		: resize(box, box.width, box.width * ratio, origin);
}

export function constrainToBoundary(
	box: Box,
	boundaryWidth: number,
	boundaryHeight: number,
	origin: Coordinates,
): Box {
	const [originX, originY] = getAbsolutePoint(box, origin);
	const maxIfLeft = originX;
	const maxIfTop = originY;
	const maxIfRight = boundaryWidth - originX;
	const maxIfBottom = boundaryHeight - originY;

	let maxWidth: number;
	if (origin[0] > 0.5) {
		maxWidth = maxIfLeft;
	} else if (origin[0] < 0.5) {
		maxWidth = maxIfRight;
	} else {
		maxWidth = Math.min(maxIfLeft, maxIfRight) * 2;
	}
	let maxHeight: number;
	if (origin[1] > 0.5) {
		maxHeight = maxIfTop;
	} else if (origin[1] < 0.5) {
		maxHeight = maxIfBottom;
	} else {
		maxHeight = Math.min(maxIfTop, maxIfBottom) * 2;
	}

	if (box.width > maxWidth) {
		return scale(box, maxWidth / box.width, origin);
	}

	if (box.height > maxHeight) {
		return scale(box, maxHeight / box.height, origin);
	}

	return box;
}
