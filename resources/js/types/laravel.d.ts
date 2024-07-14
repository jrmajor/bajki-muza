interface SharedProps {
	errors: Array<unknown>;
	user: SharedUser;
}

type SharedUser = {
	id: number;
	username: string;
} | null;

interface PaginatedResource<Resource> {
	data: Array<Resource>;
	links: PaginationLinks;
	meta: PaginationMeta;
}

interface PaginationLinks {
	first: string;
	last: string;
	prev: string | null;
	next: string | null;
}

interface PaginationMeta {
	current_page: number;
	from: 1;
	last_page: 6;
	links: Array<{
		active: boolean;
		label: string;
		url: string | null;
	}>;
	path: string;
	per_page: number;
	to: number;
	total: number;
}
