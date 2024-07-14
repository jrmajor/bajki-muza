import type { CoverResource } from './tales';

export type IndexResource = {
	slug: string;
	name: string;
	appearances: number;
	photo: PhotoResource | null;
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

export type PhotoResource = {
	facePlaceholder: string;
	url: Record<number, string>;
};

export type FullPhotoResource = {
	placeholder: string;
	aspectRatio: number | null;
	url: Record<number, string>;
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
