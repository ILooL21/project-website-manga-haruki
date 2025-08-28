@extends('image-clodinary.main')
@section('content')
    <div>
        <div class="flex justify-between mb-2">
            <a href="{{ route('image.cloudinary.create') }}"
                class="text-sm uppercase p-4 my-4 bg-green-600 hover:bg-green-700 text-white  font-bold">Add new product</a>
        </div>

        {{-- Status message --}}
        @if (session('message'))
            <p class="bg-green-500 capitalize py-1 text-lg text-white mb-2 font-bold px-4">
                {{ session('message') }}</p>
        @endif

        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            {{-- Pagination --}}
            <div class="mb-4">
                {{ $products->links() }}
            </div>
            <table class="w-full text-sm text-left text-gray-500">
                <thead class=" text-gray-200 uppercase bg-black">
                    <tr class="m-8">
                        <th scope="col" class="py-4 px-2">
                            Product name
                        </th>
                        <th scope="col" class="py-4 px-2">
                            Image
                        </th>

                        <th scope="col" class="py-4 px-2">
                            Description
                        </th>
                        <th scope="col" class="py-4 px-2">
                            Price
                        </th>

                        <th scope="col" class="py-4 px-2">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="bg-white border-b">
                            <th scope="row" class="py-3 px-6 font-medium text-gray-900 whitespace-nowrap ">
                                {{ $product->name }}
                            </th>

                            <td class="py-3 px-6">
                                <x-cloudinary::image public-id="{{ $product->image_public_id }}" alt="{{$product->name}}" width="80"
                                    height="80" class="" />
                            </td>
                            <td class="py-3 px-6 text-ellipsis">
                                {{ $product->description }}
                            </td>
                            <td class="py-3 px-6">
                                {{ $product->price }}
                            </td>
                            <td class="py-3 px-6">
                                <div class=" flex space-x-5">
                                    <a href="{{ route('image.cloudinary.edit', ['image_cloudinary' => $product->id]) }}"
                                        class="py-1.5 px-3 uppercase text-white font-medium  bg-blue-500 hover:bg-blue-700">Edit</a>
                                    <form action="{{ route('image.cloudinary.destroy', ['image_cloudinary' => $product->id]) }}" method="POST"
                                        class="py-0.5 px-3 text-white font-medium text-lg bg-red-500 hover:bg-red-700"
                                        onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class=" uppercase">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection