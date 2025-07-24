import { Head } from '@inertiajs/react';

export default function Show({ hello }) {
  return (
    <>
      <Head title={`Hello: ${hello.word}`} />

      <div className="min-h-screen bg-gray-50 py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6 text-gray-900">
              <h1 className="text-3xl font-bold mb-4">Hello Details</h1>

              <div className="bg-gray-50 p-4 rounded-lg">
                <p className="text-lg">
                  <strong>Word:</strong> {hello.word}
                </p>
                <p className="text-sm text-gray-600 mt-2">
                  <strong>ID:</strong> {hello.id}
                </p>
                <p className="text-sm text-gray-600">
                  <strong>Created:</strong>{' '}
                  {new Date(hello.created_at).toLocaleDateString()}
                </p>
              </div>

              <div className="mt-6">
                <a
                  href="/hello"
                  className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                  Back to List
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
