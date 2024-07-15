import type { CreditType } from '@/types/tales';

export const creditLabels: Record<CreditType, string> = {
	text: 'słowa',
	author: 'autor',
	lyrics: 'teksty piosenek',
	adaptation: 'adaptacja',
	translation: 'przekład',
	music: 'muzyka',
	arrangement: 'aranżacja',
	directing: 'reżyseria',
	directors_assistant: 'asystent reżysera',
	production: 'realizacja',
	producers_assistant: 'asystent realizatora',
	recording_director: 'reżyser nagrania',
	sound_operator: 'operator dźwięku',
	sound_production: 'realizacja dźwięku',
	editor: 'redaktor',
	production_manager: 'kierownik produkcji',
	artwork: 'opracowanie graficzne',
};
