import { Head, Link } from '@inertiajs/react';

export default function Index({ hellos }) {
  return (
    <>
      <Head title="Hello List" />

      <div className="min-h-screen bg-gray-50 py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6 text-gray-900">
              <h1 className="text-3xl font-bold mb-6">Hello Words</h1>

              {hellos.length > 0 ? (
                <div className="grid gap-4">
                  {hellos.map((hello) => (
                    <div
                      key={hello.id}
                      className="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors"
                    >
                      <Link href={`/hello/${hello.id}`} className="block">
                        <h3 className="text-lg font-semibold text-blue-600 hover:text-blue-800">
                          {hello.word}
                        </h3>
                        <p className="text-sm text-gray-600 mt-1">
                          ID: {hello.id} | Created:{' '}
                          {new Date(hello.created_at).toLocaleDateString()}
                        </p>
                      </Link>
                    </div>
                  ))}
                </div>
              ) : (
                <p className="text-gray-600">No hello words found.</p>
              )}
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
