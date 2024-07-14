export default function<T>(array: T[]): Array<[T, Loop]> {
	const total = array.length;

	return array.map((element, index) => [
		element,
		{
			isFirst: index === 0,
			isLast: index === total - 1,
			remaining: total - index - 1,
		} as Loop,
	]);
}

export interface Loop {
	isFirst: boolean;
	isLast: boolean;
	remaining: number;
}
