import type { CoverResource } from './tales';

export type IndexResource = {
	slug: string;
	name: string;
	appearances: number;
	photo: FacePhotoResource | null;
	discogsPhotoThumb: string | null;
};

export type ShowResource = {
	slug: string;
	name: string;
	appearances: number;
	photo: FullPhotoResource | null;
	discogsUrl: string | null;
	discogsPhoto: string | null;
	filmpolskiUrl: string | null;
	wikipediaUrl: string | null;
	wikipediaExtract: string | null;
	asActor: ActorResource[];
	credits: Record<string, CreditResource[]>;
};

export type EditResource = {
	slug: string;
	name: string;
	genetivus: string | null;
	discogs: number | null;
	filmpolski: number | null;
	wikipedia: string | null;
	photo: EditPhotoResource | null;
	discogsPhotos: DiscogsPhotoResource[];
	filmPolskiPhotos: FilmPolskiPhotoGroupResource[];
};

export type FacePhotoResource = {
	placeholder: string;
	url: string;
};

export type FullPhotoResource = {
	placeholder: string;
	width: number | null;
	height: number | null;
	url: string;
};

export type EditPhotoResource = {
	url: string;
	source: string | null;
	crop: ArtistPhotoCrop;
	grayscale: boolean;
};

export type ArtistPhotoCrop = {
	face: ArtistFaceCrop;
	image: ArtistImageCrop;
};

export type ArtistFaceCrop = {
	x: number;
	y: number;
	size: number;
};

export type ArtistImageCrop = {
	x: number;
	y: number;
	width: number;
	height: number;
};

export type ActorResource = {
	characters: string[] | null;
	slug: string;
	title: string;
	year: number | null;
	cover: CoverResource | null;
};

export type CreditResource = {
	slug: string;
	title: string;
	year: number | null;
	cover: CoverResource | null;
};

export type DiscogsPhotoResource = {
	uri: string;
	width: number;
	height: number;
};

export type FilmPolskiPhotoGroupResource = {
	title: string | null;
	year: number | null;
	photos: string[];
};
