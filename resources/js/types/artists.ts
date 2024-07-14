export type IndexResource = {
	id: number;
	slug: string;
	name: string;
	appearances: number;
	photo: PhotoResource | null;
	discogsPhotoThumb: string | null;
};

export type PhotoResource = {
	placeholder: string;
	facePlaceholder: string;
	url: Record<number, string>;
};
