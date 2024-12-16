import type { ArtistFaceCrop } from '@/types/artists';
import type { Box } from '@/Components/Cropper/box';

export function faceCropToBox(crop: ArtistFaceCrop): Box {
	return {
		x: crop.x,
		y: crop.y,
		width: crop.size,
		height: crop.size,
	};
}

export function boxToFaceCrop(box: Box): ArtistFaceCrop {
	return {
		x: box.x,
		y: box.y,
		size: box.width,
	};
}
