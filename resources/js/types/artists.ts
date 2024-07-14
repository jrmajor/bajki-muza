export type IndexResource = {
	slug: string;
	name: string;
	appearances: number;
	photo: PhotoResource | null;
	discogsPhotoThumb: string | null;
};

export type PhotoResource = {
	facePlaceholder: string;
	url: Record<number, string>;
};
