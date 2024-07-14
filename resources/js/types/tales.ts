import type { PhotoResource } from './artists';

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
	cover: CoverResource | null;
	actors: ActorResource[];
	mainCredits: Record<MainCreditType, CreditResource[]>;
	customCredits: Record<string, CreditResource[]>;
};

export type CoverResource = {
	placeholder: string;
	url: Record<number, string>;
};

export type ActorResource = {
	characters: string[] | null;
	slug: string;
	name: string;
	appearances: number;
	photo: PhotoResource | null;
	discogsPhotoThumb: string | null;
};

export type CreditResource = {
	as: string | null;
	slug: string;
	name: string;
	genetivus?: string | null;
	appearances: number;
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
