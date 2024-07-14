const listFormat = new Intl.ListFormat('pl');

export function formatList(list: Iterable<string>) {
	return listFormat.format(list);
}

export function ucfirst(str: string) {
	return str.slice(0, 1).toLocaleUpperCase('pl') + str.slice(1);
}
