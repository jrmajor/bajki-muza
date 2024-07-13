export type IndexResource = {
	id: number;
	slug: string;
	title: string;
	year: number | null;
	cover: CoverResource | null;
};

export type CoverResource = {
	placeholder: string;
	url: Record<number, string>;
};
