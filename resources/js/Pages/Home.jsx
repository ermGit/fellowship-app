import { Head } from '@inertiajs/react';
import Layout from '../Components/Layout';

export default function Home() {
    return (
        <Layout>
            <Head title="Home" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div className="p-6 sm:p-8">
                            <div className="text-center mb-8">
                                <h2 className="text-4xl font-bold text-amber-900 mb-4">
                                    Welcome to The Fellowship of the Tee
                                </h2>
                                <p className="text-lg text-gray-600">
                                    Embark on an epic journey through Middle-earth literature
                                </p>
                            </div>
                            <div className="flex justify-center">
                                <div className="max-w-2xl">
                                    <img
                                        src="/images/poster.jpg"
                                        alt="Fellowship of the Tee Poster"
                                        className="rounded-lg shadow-2xl w-full h-auto hover:scale-105 transition-transform duration-300"
                                    />
                                </div>
                            </div>
                            <div className="mt-8 text-center">
                                <p className="text-gray-700 text-lg">
                                    Navigate to the <strong>Search</strong> page to explore the books of Middle-earth
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
