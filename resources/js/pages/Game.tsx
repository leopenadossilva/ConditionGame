import { FormEvent, useEffect, useRef, useState } from 'react';

interface Player {
    id: number;
    name: string;
    hp: number;
    max_hp: number;
    level: number;
    xp: number;
    gold: number;
}

interface Enemy {
    id: number;
    name: string;
    description: string | null;
    hp: number;
    max_hp: number;
    level: number;
    xp_reward: number;
    gold_reward: number;
}

interface ToolCall {
    name: string;
    arguments: Record<string, unknown>;
    result: Record<string, unknown>;
}

interface ChatMessage {
    id: number;
    role: 'player' | 'sage';
    text: string;
    toolCalls?: ToolCall[];
    mode?: string;
}

interface Props {
    player: Player;
    enemies: Enemy[];
}

const SUGGESTIONS = [
    'What enemies are out there?',
    'How am I doing?',
    'Who should I fight first?',
];

let nextId = 1;

export default function Game({ player, enemies }: Props) {
    const [messages, setMessages] = useState<ChatMessage[]>([
        {
            id: nextId++,
            role: 'sage',
            text: `I'm Sage, your helper. I can read live game data — ask me about enemies or your own status.`,
        },
    ]);
    const [input, setInput] = useState('');
    const [busy, setBusy] = useState(false);
    const [mode, setMode] = useState<string>('idle');
    const logRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        logRef.current?.scrollTo({ top: logRef.current.scrollHeight, behavior: 'smooth' });
    }, [messages, busy]);

    async function ask(message: string) {
        const question = message.trim();

        if (!question || busy) {
            return;
        }

        setMessages((current) => [...current, { id: nextId++, role: 'player', text: question }]);
        setInput('');
        setBusy(true);

        try {
            const response = await fetch('/api/agent/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ message: question }),
            });

            if (!response.ok) {
                throw new Error(`Request failed: ${response.status}`);
            }

            const data = await response.json();

            setMode(data.mode ?? 'unknown');
            setMessages((current) => [
                ...current,
                {
                    id: nextId++,
                    role: 'sage',
                    text: data.reply,
                    toolCalls: data.tool_calls ?? [],
                    mode: data.mode,
                },
            ]);
        } catch {
            setMessages((current) => [
                ...current,
                { id: nextId++, role: 'sage', text: 'I could not reach the game world. Try again.' },
            ]);
        } finally {
            setBusy(false);
        }
    }

    function onSubmit(event: FormEvent) {
        event.preventDefault();
        void ask(input);
    }

    return (
        <div className="mx-auto flex h-full max-w-[1400px] flex-col gap-5 p-5">
            <header className="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-800 pb-4">
                <div className="flex items-baseline gap-3">
                    <h1 className="text-2xl font-semibold tracking-tight text-white">ConditionGame</h1>
                    <span className="rounded border border-neutral-700 px-2 py-0.5 text-xs text-neutral-400">v1</span>
                </div>
                <div className="flex items-center gap-4 text-sm text-neutral-400">
                    <span className="rounded-full bg-neutral-900 px-3 py-1 ring-1 ring-neutral-800">
                        helper mode: <span className="text-emerald-400">{mode}</span>
                    </span>
                    <span>
                        {player.name} · Lv {player.level} · {player.gold} gold
                    </span>
                </div>
            </header>

            <main className="grid min-h-0 flex-1 gap-5 lg:grid-cols-[1.6fr_1fr]">
                <section className="flex min-h-0 flex-col gap-4 rounded-xl border border-neutral-800 bg-neutral-900/50 p-5">
                    <div className="flex items-center justify-between">
                        <h2 className="text-sm font-medium uppercase tracking-wide text-neutral-400">The Hollow</h2>
                        <span className="text-xs text-neutral-500">{enemies.length} enemies</span>
                    </div>

                    <div className="grid gap-3 overflow-y-auto pr-1 sm:grid-cols-2">
                        {enemies.map((enemy) => (
                            <article key={enemy.id} className="rounded-lg border border-neutral-800 bg-neutral-950/70 p-4">
                                <div className="mb-1 flex items-baseline justify-between gap-2">
                                    <h3 className="font-medium text-white">{enemy.name}</h3>
                                    <span className="text-xs text-neutral-500">Lv {enemy.level}</span>
                                </div>
                                <p className="mb-3 min-h-9 text-xs leading-5 text-neutral-400">{enemy.description}</p>
                                <div className="mb-2 h-1.5 overflow-hidden rounded-full bg-neutral-800">
                                    <div
                                        className="h-full rounded-full bg-red-500/80"
                                        style={{ width: `${Math.round((enemy.hp / Math.max(enemy.max_hp, 1)) * 100)}%` }}
                                    />
                                </div>
                                <div className="flex justify-between text-[11px] text-neutral-500">
                                    <span>
                                        {enemy.hp}/{enemy.max_hp} HP
                                    </span>
                                    <span>
                                        {enemy.xp_reward} XP · {enemy.gold_reward} gold
                                    </span>
                                </div>
                            </article>
                        ))}
                    </div>

                    <div className="mt-auto rounded-lg border border-neutral-800 bg-neutral-950/70 p-4">
                        <div className="mb-2 flex items-center justify-between text-xs text-neutral-400">
                            <span className="uppercase tracking-wide">{player.name}</span>
                            <span>Lv {player.level}</span>
                        </div>
                        <div className="mb-2 h-2 overflow-hidden rounded-full bg-neutral-800">
                            <div
                                className="h-full rounded-full bg-emerald-500/80"
                                style={{ width: `${Math.round((player.hp / Math.max(player.max_hp, 1)) * 100)}%` }}
                            />
                        </div>
                        <div className="grid grid-cols-3 gap-2 text-center text-xs text-neutral-400">
                            <div>
                                <div className="text-base text-white">{player.hp}</div>
                                HP
                            </div>
                            <div>
                                <div className="text-base text-white">{player.xp}</div>
                                XP
                            </div>
                            <div>
                                <div className="text-base text-white">{player.gold}</div>
                                gold
                            </div>
                        </div>
                    </div>
                </section>

                <section className="flex min-h-0 flex-col rounded-xl border border-neutral-800 bg-neutral-900/50">
                    <div className="flex items-center gap-3 border-b border-neutral-800 p-4">
                        <div className="grid h-10 w-10 place-items-center rounded-full bg-gradient-to-br from-emerald-400 to-cyan-500 text-lg font-semibold text-neutral-950">
                            S
                        </div>
                        <div>
                            <div className="text-sm font-medium text-white">Sage</div>
                            <div className="text-xs text-neutral-500">game helper · tool-enabled</div>
                        </div>
                    </div>

                    <div ref={logRef} className="flex-1 space-y-4 overflow-y-auto p-4">
                        {messages.map((message) => (
                            <div key={message.id} className={message.role === 'player' ? 'flex justify-end' : 'flex justify-start'}>
                                <div
                                    className={
                                        message.role === 'player'
                                            ? 'max-w-[85%] rounded-2xl rounded-br-sm bg-sky-600 px-4 py-2.5 text-sm text-white'
                                            : 'max-w-[90%] rounded-2xl rounded-bl-sm bg-neutral-800 px-4 py-2.5 text-sm text-neutral-200'
                                    }
                                >
                                    <p className="whitespace-pre-wrap leading-6">{message.text}</p>

                                    {message.toolCalls && message.toolCalls.length > 0 && (
                                        <div className="mt-2 flex flex-wrap gap-1.5 border-t border-neutral-700/70 pt-2">
                                            {message.toolCalls.map((call, index) => (
                                                <span
                                                    key={`${message.id}-${index}`}
                                                    className="rounded bg-neutral-950/60 px-2 py-0.5 font-mono text-[10px] text-emerald-400"
                                                    title={JSON.stringify(call.result)}
                                                >
                                                    {call.name}
                                                    {Object.keys(call.arguments).length > 0
                                                        ? `(${JSON.stringify(call.arguments)})`
                                                        : '()'}
                                                </span>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            </div>
                        ))}

                        {busy && (
                            <div className="flex justify-start">
                                <div className="rounded-2xl rounded-bl-sm bg-neutral-800 px-4 py-3">
                                    <span className="inline-flex gap-1">
                                        <span className="h-1.5 w-1.5 animate-bounce rounded-full bg-neutral-500 [animation-delay:0ms]" />
                                        <span className="h-1.5 w-1.5 animate-bounce rounded-full bg-neutral-500 [animation-delay:120ms]" />
                                        <span className="h-1.5 w-1.5 animate-bounce rounded-full bg-neutral-500 [animation-delay:240ms]" />
                                    </span>
                                </div>
                            </div>
                        )}
                    </div>

                    <div className="border-t border-neutral-800 p-3">
                        <div className="mb-2 flex flex-wrap gap-1.5">
                            {SUGGESTIONS.map((suggestion) => (
                                <button
                                    key={suggestion}
                                    type="button"
                                    onClick={() => void ask(suggestion)}
                                    disabled={busy}
                                    className="rounded-full border border-neutral-700 px-2.5 py-1 text-[11px] text-neutral-400 transition hover:border-neutral-500 hover:text-neutral-200 disabled:opacity-50"
                                >
                                    {suggestion}
                                </button>
                            ))}
                        </div>

                        <form onSubmit={onSubmit} className="flex gap-2">
                            <input
                                value={input}
                                onChange={(event) => setInput(event.target.value)}
                                placeholder="Ask Sage about the world..."
                                className="min-w-0 flex-1 rounded-lg border border-neutral-700 bg-neutral-950 px-3 py-2 text-sm text-neutral-100 outline-none placeholder:text-neutral-600 focus:border-emerald-500/70"
                            />
                            <button
                                type="submit"
                                disabled={busy || input.trim() === ''}
                                className="rounded-lg bg-emerald-500 px-4 py-2 text-sm font-medium text-neutral-950 transition hover:bg-emerald-400 disabled:opacity-50"
                            >
                                Send
                            </button>
                        </form>
                    </div>
                </section>
            </main>
        </div>
    );
}
