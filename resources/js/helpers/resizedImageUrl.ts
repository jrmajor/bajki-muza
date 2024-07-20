export default function (url: string, height: number) {
	const params = new URLSearchParams();
	params.append('url', url);
	params.append('h', height.toString());
	return `https://wsrv.nl/?${params.toString()}`;
}
