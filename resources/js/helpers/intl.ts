const listFormat = new Intl.ListFormat('pl');

export function formatList(list: Iterable<string>) {
	return listFormat.format(list);
}
