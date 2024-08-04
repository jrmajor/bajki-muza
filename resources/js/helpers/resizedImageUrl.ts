export default function resizedImageUrl(url: string, height: number) {
	const params = new URLSearchParams({ url, h: height.toString() });
	return `https://wsrv.nl/?${params}`;
}
