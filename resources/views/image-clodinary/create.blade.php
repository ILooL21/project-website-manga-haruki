@extends('image-clodinary.main')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('image.cloudinary.index')}}"
                    class="px-10 py-2 text-white bg-blue-600 hover:bg-blue-400 uppercase">Go Back</a>
            </div>

            @if ($errors->any())
                <ul class="p-2 text-red-600 font-semibold list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('image.cloudinary.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label for="name" class="block mb-2 text-lg text-gray-600 font-bold">Product Name</label>
                    <input type="text"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-black focus:border focus:border-black  block w-1/2 p-2.5"
                        name="name" required value="{{ old('name') }}">
                </div>

                <div class="mb-6">
                    <label for="description" class="block mb-2 text-lg text-gray-600 font-bold">Description</label>
                    <textarea name="description" id="" cols="30" rows="5"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-gray-400  focus:border-gray-400  block w-1/2 p-2">{{ old('description') }}</textarea>
                </div>

                <div class="mb-6">

                    <label class="block mb-2 text-lg  text-gray-600 font-bold" for="images">Upload image</label>
                    <input
                        class="block w-1/2 p-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer"
                        type="file" name="image" required>
                </div>

                <div class="mb-6">
                    <label for="price" class="block mb-2 text-lg text-gray-600 font-bold">Price</label>
                    <input type="number" name="price"
                        class="block w-1/2 p-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-gray-400  focus:border-gray-400"
                        required value="{{ old('price') }}">
                </div>

                <button type="submit"
                    class="my-8 w-48 h-12 bg-emerald-600 hover:bg-emerald-500 rounded-md text-lg text-white">Create
                    Product</button>
            </form>

        </div>
    </div>
@endsection