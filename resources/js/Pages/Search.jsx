import { Head } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import Layout from '../Components/Layout';

// Displays the book titles which contain the User's input
export default function Search() {
    const [books, setBooks] = useState([]);
    const [filteredBooks, setFilteredBooks] = useState([]);
    const [searchTerm, setSearchTerm] = useState('');
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    // Executes once
    useEffect(() => {
        fetchBooks();
    }, []);

    // Executes whenever searchTerm or books changes
    useEffect(() => {
        if (searchTerm === '') {
            // Sets the books to display to all
            // books from the initial fetchBooks call
            setFilteredBooks(books);
        } else {
            // Filters the book names to show only
            // those that include the search term.
            const filtered = books.filter(book =>
                book.name.toLowerCase().includes(searchTerm.toLowerCase())
            );
            setFilteredBooks(filtered);
        }
    }, [searchTerm, books]);

    const fetchBooks = async () => {
        try {
            setLoading(true);
            const response = await fetch('/api/books');

            // If the API call returns an error
            if (!response.ok) {
                throw new Error('Failed to fetch books');
            }
            
            const data = await response.json();

            // Sets both Books and FilteredBooks states as all books
            setBooks(data);
            setFilteredBooks(data);
            setError(null);
        } catch (err) {
            setError(err.message);
            console.error('Error fetching books:', err);
        } finally {
            setLoading(false);
        }
    };

    return (
        <Layout>
            <Head title="Search" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 sm:p-8">
                        <div className="mb-8">
                            <h2 className="text-3xl font-bold text-amber-900 mb-4">
                                Search Middle-earth Books
                            </h2>
                            <div className="relative">
                                <input
                                    type="text"
                                    placeholder="Search books..."
                                    value={searchTerm}
                                    onChange={(e) => setSearchTerm(e.target.value)}
                                    className="w-full px-4 py-3 border-2 border-amber-200 rounded-lg focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-200 text-lg transition-all duration-200"
                                />
                                {searchTerm && (
                                    <button
                                        onClick={() => setSearchTerm('')}
                                        className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        ✕
                                    </button>
                                )}
                            </div>
                            <p className="mt-2 text-sm text-gray-600">
                                Found {filteredBooks.length} book{filteredBooks.length !== 1 ? 's' : ''}
                            </p>
                        </div>

                        {loading && (
                            <div className="text-center py-12">
                                <div className="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-amber-900"></div>
                                <p className="mt-4 text-gray-600">Loading books...</p>
                            </div>
                        )}

                        {error && (
                            <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                                <strong className="font-bold">Error: </strong>
                                <span className="block sm:inline">{error}</span>
                            </div>
                        )}

                        {!loading && !error && (
                            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                {filteredBooks.map((book, index) => (
                                    <div
                                        key={index}
                                        className="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 p-6 border-2 border-amber-200"
                                    >
                                        <div className="flex items-center justify-center h-full">
                                            <h3 className="text-lg font-semibold text-amber-900 text-center">
                                                {book.name}
                                            </h3>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}

                        {!loading && !error && filteredBooks.length === 0 && (
                            <div className="text-center py-12">
                                <p className="text-xl text-gray-600">
                                    No books found matching "{searchTerm}"
                                </p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </Layout>
    );
}
