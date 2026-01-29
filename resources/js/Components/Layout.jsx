import { Link } from '@inertiajs/react';

export default function Layout({ children }) {
    return (
        <div className="min-h-screen bg-gradient-to-b from-amber-50 to-stone-100">
            <nav className="bg-amber-900 text-amber-50 shadow-lg">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex space-x-8">
                            <Link
                                href="/"
                                className="inline-flex items-center px-4 pt-1 border-b-2 border-transparent text-lg font-semibold hover:border-amber-300 transition-colors duration-200"
                            >
                                Home
                            </Link>
                            <Link
                                href="/search"
                                className="inline-flex items-center px-4 pt-1 border-b-2 border-transparent text-lg font-semibold hover:border-amber-300 transition-colors duration-200"
                            >
                                Search
                            </Link>
                        </div>
                        <div className="flex items-center">
                            <h1 className="text-2xl font-bold text-amber-100 tracking-wide">
                                The Fellowship of the Tee
                            </h1>
                        </div>
                    </div>
                </div>
            </nav>
            <main>{children}</main>
        </div>
    );
}
