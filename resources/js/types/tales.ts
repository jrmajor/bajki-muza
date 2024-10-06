import type { FacePhotoResource } from './artists';

export type IndexResource = {
	slug: string;
	title: string;
	year: number | null;
	cover: CoverResource | null;
};

export type ShowResource = {
	slug: string;
	title: string;
	year: number | null;
	cover: FullCoverResource | null;
	actors: ActorResource[];
	mainCredits: Record<MainCreditType, CreditResource[]>;
	customCredits: Record<string, CreditResource[]>;
};

export type EditResource = {
	slug: string;
	title: string;
	year: number | null;
	nr: string | null;
	discogs: number | null;
	notes: string | null;
	cover: CoverResource | null;
	credits: CreditEditResource[];
	actors: ActorEditResource[];
};

export type CoverResource = {
	placeholder: string;
	url: string;
};

export type FullCoverResource = {
	placeholder: string;
	size: number | null;
	url: string;
};

export type ActorResource = {
	characters: string[] | null;
	slug: string;
	name: string;
	appearances: number;
	photo: FacePhotoResource | null;
	discogsPhotoThumb: string | null;
};

export type CreditResource = {
	as: string | null;
	slug: string;
	name: string;
	genetivus?: string | null;
	appearances: number;
};

export type CreditEditResource = {
	artist: string;
	type: CreditType;
	as: string | null;
	nr: number | null;
};

export type ActorEditResource = {
	artist: string;
	characters: string | null;
};

export type MainCreditType = 'text' | 'author' | 'lyrics' | 'music';

export type CustomCreditType =
	| 'adaptation'
	| 'translation'
	| 'arrangement'
	| 'directing'
	| 'directors_assistant'
	| 'production'
	| 'producers_assistant'
	| 'recording_director'
	| 'sound_operator'
	| 'sound_production'
	| 'editor'
	| 'production_manager'
	| 'artwork';

export type CreditType = MainCreditType | CustomCreditType;
